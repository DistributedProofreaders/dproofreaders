/* global QUnit */

import { testText } from "../../../scripts/character_test.js";

QUnit.module("Character validation test", function () {
    let basicLatin = /^(?:[\n\r -~¡-¬®-ÿŒœŠšŽžŸƒ‹›])$/u;

    QUnit.test("In basic latin basic latin character is valid", function (assert) {
        window.validCharRegex = basicLatin;
        assert.strictEqual(testText("abc"), true);
    });

    QUnit.test("In basic latin tabulate character is invalid", function (assert) {
        window.validCharRegex = basicLatin;
        assert.strictEqual(testText("\tabc"), false);
    });

    QUnit.test("In basic latin Greek Α is invalid", function (assert) {
        window.validCharRegex = basicLatin;
        assert.strictEqual(testText("Αabc"), false);
    });

    QUnit.test("In basic latin Astral char. is invalid", function (assert) {
        window.validCharRegex = basicLatin;
        assert.strictEqual(testText("ab\u{1F702}c"), false);
    });

    QUnit.test("In basic latin A with combining diaeresis is valid when normalised, b is not", function (assert) {
        window.validCharRegex = basicLatin;
        assert.strictEqual(testText("a\u0308bc"), true);
        assert.strictEqual(testText("ab\u0308c"), false);
    });

    QUnit.test("In basic Greek, Greek Α is valid", function (assert) {
        window.validCharRegex = /^(?:[\n\rΑ-ΡΣ-Ωα-ω -~¡-¬®-ÿŒœŠšŽžŸƒ‹›])$/u;
        assert.strictEqual(testText("Αabc"), true);
    });

    QUnit.test("characters with combining marks", function (assert) {
        window.validCharRegex = /^(?:S̤|T̤|Z̤|s̤|t̤|z̤|[\n\r Ā-āĒ-ēĪ-īŌ-ōŚ-śŠ-šŪ-ūʾ-ʿḌ-ḍḤ-ḥḪ-ḫḲ-ḳḷḹṀ-ṇṚ-ṝṢ-ṣṬ-ṭẒ-ẖ])$/u;
        assert.strictEqual(testText("S\u0324"), true); // S with macron below
        assert.strictEqual(testText("U\u0324"), false); // U with macron below
        assert.strictEqual(testText("\u1e72"), false); // U with macron below normalised
        assert.strictEqual(testText("M\u0307"), true); // M with dot not normalised
        assert.strictEqual(testText("\u1e40"), true); // M with dot normalised
    });

    QUnit.test("Astral plane", function (assert) {
        window.validCharRegex = /^(?:[\n\r 𓀀-𓀂])$/u;
        assert.strictEqual(testText("𓀀"), true);
        assert.strictEqual(testText("\u{13000}"), true);
        assert.strictEqual(testText("𓀂"), true);
        assert.strictEqual(testText("𓀁"), true);
        assert.strictEqual(testText("𓀉"), false);
    });
});
