<?php

use Symfony\Component\Process\Process;

class ImageUtils
{
    public const IMAGE_OK = 1;
    public const IMAGE_WARNING = 2;
    public const IMAGE_CORRUPT = 3;
    public const IMAGE_SKIPPED = 4;

    /** @var array<string, bool> */
    protected array $external_tools = [
        "pngcheck" => false,
        "jpeginfo" => false,
    ];

    // These strings are returned from the validation tools but don't
    // prevent the image from being loaded/used. Let's treat these substrings
    // as warnings, not errors.
    /** @var string[] */
    public array $warning_strings = [
        "illegal (unless recently approved) unknown, public chunk eXIf",
        "invalid gAMA value (0.0000)",
        "invalid tIME year (1969)",
        "tEXt text contains NULL character(s)",
    ];

    public function __construct()
    {
        $this->check_external_tools();
    }

    protected function check_external_tools(): void
    {
        foreach (array_keys($this->external_tools) as $tool) {
            $process = new Process([$tool, "-h"]);
            $process->run();
            if ($process->isSuccessful()) {
                $this->external_tools[$tool] = true;
            }
        }
    }

    /**
     * Checks if this class can validate image files given an extension.
     */
    public function can_validate(string $extension): bool
    {
        $extension = strtolower($extension);
        if ($extension == "png" && $this->external_tools["pngcheck"]) {
            return true;
        } elseif (($extension == "jpeg" || $extension == "jpg") && $this->external_tools["jpeginfo"]) {
            return true;
        }

        return false;
    }

    /**
     * Validates integrity of the specified image using external tools.
     *
     * @returns array<int, string>
     */
    public function validate_integrity(string $filename): array
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!$this->can_validate($extension)) {
            return [ImageUtils::IMAGE_SKIPPED, ""];
        }

        if ($extension == "png") {
            $checker = ["pngcheck"];
        } elseif ($extension == "jpeg" || $extension == "jpg") {
            $checker = ["jpeginfo", "--check"];
        } else {
            throw new LogicException("Unsupported extension found, update can_validate()");
        }

        $checker[] = $filename;
        $process = new Process($checker);
        $process->run();
        if (!$process->isSuccessful()) {
            $output = explode("\n", $process->getOutput());

            // remove the filename from the string, it doesn't add value
            $output = trim(str_replace($filename, "", $output[0]));

            // if its one of our known warning strings, downgrade the severity
            foreach ($this->warning_strings as $string) {
                if (stripos($output, $string) !== false) {
                    return [ImageUtils::IMAGE_WARNING, $output];
                }
            }

            return [ImageUtils::IMAGE_CORRUPT, $output];
        }

        return [ImageUtils::IMAGE_OK, ""];
    }
}
