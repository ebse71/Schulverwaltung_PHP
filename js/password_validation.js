document.getElementById('new_password').addEventListener('input', validatePassword);
document.getElementById('confirm_password').addEventListener('input', validatePassword);

function validatePassword() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Regeln
    const lengthRule = document.getElementById('length-rule');
    const lowercaseRule = document.getElementById('lowercase-rule');
    const uppercaseRule = document.getElementById('uppercase-rule');
    const symbolRule = document.getElementById('symbol-rule');
    const numberRule = document.getElementById('number-rule');
    const matchRule = document.getElementById('match-rule');

    // Passwortüberprüfung
    let isLengthValid = password.length >= 10;
    let isLowercaseValid = /[a-z]/.test(password);
    let isUppercaseValid = /[A-Z]/.test(password);
    let isSymbolValid = /[\W]/.test(password); // Sonderzeichen
    let isNumberValid = /\d/.test(password);
    let isMatchValid = password === confirmPassword;

    // Regeln entsprechend dem Zustand aktualisieren
    updateRuleState(lengthRule, isLengthValid);
    updateRuleState(lowercaseRule, isLowercaseValid);
    updateRuleState(uppercaseRule, isUppercaseValid);
    updateRuleState(symbolRule, isSymbolValid);
    updateRuleState(numberRule, isNumberValid);
    updateRuleState(matchRule, isMatchValid);

    // Überprüfung, ob alle Regeln gültig sind
    const isFormValid = isLengthValid && isLowercaseValid && isUppercaseValid && isSymbolValid && isNumberValid && isMatchValid;

    // Button aktivieren oder deaktivieren
    document.getElementById('submit-button').disabled = !isFormValid;
}

function updateRuleState(ruleElement, isValid) {
    if (isValid) {
        ruleElement.classList.remove('invalid');
        ruleElement.classList.add('valid');
    } else {
        ruleElement.classList.remove('valid');
        ruleElement.classList.add('invalid');
    }
}

// Passwort ein-/ausblenden
document.querySelectorAll('.toggle-password').forEach(item => {
    item.addEventListener('click', function () {
        const target = this.previousElementSibling;
        if (target.type === 'password') {
            target.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            target.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
});
