// reload.js
// Überprüfen, ob die Seite zuvor neu geladen wurde
if (!localStorage.getItem('reloaded')) {
    // Wenn die Seite zuvor nicht neu geladen wurde, neu laden
    localStorage.setItem('reloaded', 'true');
    window.location.reload(true);
} else {
    // Entfernen des Werts nach dem Neuladen der Seite
    localStorage.removeItem('reloaded');
}
