class NipValidator {
    constructor() {
        this.weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
    }

    validate(nip) {
        if (!nip) return true;
        if (typeof nip !== "string") return false;

        // nip = nip.replace(/[\ \-]/gi, "");

        const controlNumber = parseInt(nip.substring(9, 10), 10);
        const sum = this.weights.reduce(
            (acc, w, i) => acc + parseInt(nip[i], 10) * w,
            0
        );

        return sum % 11 === controlNumber;
    }
}

export default NipValidator;
