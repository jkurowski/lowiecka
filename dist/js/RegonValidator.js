class RegonValidator {
    constructor() {
        this.weights9 = [8, 9, 2, 3, 4, 5, 6, 7];
        this.weights14 = [2, 4, 8, 5, 0, 9, 7, 3, 6, 1, 2, 4, 8];
    }

    calculateChecksum(regon, weights) {
        return regon
            .split("")
            .slice(0, weights.length)
            .reduce((sum, digit, index) => sum + digit * weights[index], 0);
    }

    validate(regon) {
        if (!regon) return true;
        const regonLength = regon.length;
        const weights = regonLength === 9 ? this.weights9 : this.weights14;
        let checksum = this.calculateChecksum(regon, weights) % 11;
        checksum = checksum === 10 ? 0 : checksum;
        return checksum === parseInt(regon[regonLength - 1], 10);
    }
}

export default RegonValidator;
