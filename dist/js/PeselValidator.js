class PeselValidator {
  constructor() {
    this.weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
  }

  calculateChecksum(pesel) {
    return Array.from(pesel)
      .slice(0, this.weights.length)
      .reduce((sum, digit, index) => sum + digit * this.weights[index], 0);
  }

  validate(pesel) {
    if (!pesel) return true;
    if (!this.isValidPesel(pesel)) {
      return false;
    }
    const checksum = this.calculateChecksum(pesel);
    const calculatedChecksum = (10 - (checksum % 10)) % 10;
    return calculatedChecksum === parseInt(pesel[pesel.length - 1], 10);
  }

  isValidPesel(pesel) {
    return this.validateLength(pesel) && this.isNumeric(pesel);
  }

  validateLength(pesel) {
    return pesel.length === 11;
  }

  isNumeric(pesel) {
    return /^\d+$/.test(pesel);
  }
}

export default PeselValidator;