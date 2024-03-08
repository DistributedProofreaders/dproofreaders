window.addEventListener('DOMContentLoaded', function() {
  const conversionMap = {
    '‘': '\'',
    '“': '"',
    '’': '\'',
    '”': '"',
    '—': '--'
  };

  document.getElementById('text_data').addEventListener('input', (event) => {
    const textDataElement = event.target;
    let selectionEnd = textDataElement.selectionEnd;
    textDataElement.value = [...textDataElement.value].map((character) => {
        const conversion = conversionMap[character] || character;
        selectionEnd += (conversion.length - 1);
        return conversion;
    }).join('');
    textDataElement.selectionStart = selectionEnd;
    textDataElement.selectionEnd = selectionEnd;
  });
});