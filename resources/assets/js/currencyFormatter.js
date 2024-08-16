(function (global) {
  function formatCurrencyInput(inputElement) {
    let lastValue = '';

    inputElement.addEventListener('blur', function (e) {
      const currentValue = e.target.value;

      // Replace commas with periods and remove existing periods
      const value = currentValue.replace(',', '.').replace(/\.(?=.*\.)/, '');

      // If the current value is empty, set the input value to an empty string
      if (currentValue.trim() === '') {
        this.value = '';
        lastValue = '';
        return;
      }

      // If the value did not change, do not reformat
      if (currentValue === lastValue) {
        return;
      }

      // Format with thousand separators and two decimal places
      this.value = parseFloat(value).toLocaleString('id-ID', {
        style: 'decimal',
        maximumFractionDigits: 4,
        minimumFractionDigits: 2
      });

      // Save the formatted value
      lastValue = this.value;
    });
  }

  // Expose library to the global object
  global.formatCurrencyInput = formatCurrencyInput;
})(window);
