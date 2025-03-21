const copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        showNotification("Link skopiowany");
    } catch (err) {
        fallbackCopyToClipboard(text);
    }
};

// Fallback for browsers that don't support Clipboard API
const fallbackCopyToClipboard = (text) => {
    const tempInput = document.createElement("input");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    showNotification("Link skopiowany");
};

const showNotification = (message, type = "success") => {
    const toastrConfig = {
        closeButton: true,
        progressBar: true,
        timeOut: 3000,
    };

    Object.assign(toastr.options, toastrConfig);
    switch (type) {
        case "success":
            toastr.success(message);
            break;
        case "error":
            toastr.error(message);
            break;
        default:
            toastr.success(message);
            break;
    }
};

const utils = {
    copyToClipboard,
    showNotification,
};

window.utils = utils;
