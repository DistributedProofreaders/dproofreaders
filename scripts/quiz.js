/* global makeQuizTextWidget constructToolBox */

window.addEventListener("DOMContentLoaded", () => {
    const textDiv = document.getElementById("quiz_text");
    const initialText = textDiv.dataset.initial_text;
    const pickerData = JSON.parse(textDiv.dataset.pickersets);
    const widgetText = JSON.parse(textDiv.dataset.widget_text);
    const roundType = textDiv.dataset.round_type;

    const quizTextWidget = makeQuizTextWidget(textDiv, {}, widgetText);
    constructToolBox(quizTextWidget, pickerData, roundType, {}, "quiz");
    function initialiseText() {
        quizTextWidget.setText(initialText);
    }

    document.getElementById("editform").addEventListener("submit", function () {
        document.getElementById("text_data").value = quizTextWidget.getText();
    });

    document.getElementById("restart").addEventListener("click", initialiseText);

    const cheatButton = document.getElementById("cheat_button");
    cheatButton.addEventListener("click", function () {
        quizTextWidget.setText(cheatButton.dataset.cheat_text);
    });

    initialiseText();
});
