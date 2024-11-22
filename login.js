document.getElementById("login-form").addEventListener("input", function() {
    const username = document.getElementById("nombre").value;
    const password = document.getElementById("contraseña").value;
    document.getElementById("submit-btn").disabled = !(username && password);
});

document.getElementById("contraseña").addEventListener("input", function() {
    const password = document.getElementById("contraseña").value;
    let strength = "Débil";
    if (password.length >= 8) strength = "Moderada";
    if (password.length >= 12 && /[A-Z]/.test(password) && /\d/.test(password)) strength = "Fuerte";

    document.getElementById("strength-indicator").textContent = `Fortaleza: ${strength}`;
});
