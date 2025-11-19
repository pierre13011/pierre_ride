export default class InscriptionView {
  constructor() { this.form = document.getElementById('form-inscription'); }
  getFormData() {
    return {
      nom: document.getElementById('nom').value.trim(),
      prenom: document.getElementById('prenom').value.trim(),
      pseudo: document.getElementById('pseudo').value.trim(),
      email: document.getElementById('email').value.trim(),
      password: document.getElementById('password').value,
      confirm: document.getElementById('confirm-password').value,
      chauffeur: document.getElementById('chauffeur').checked
    };
  }
  showSuccess(prenom) {
    const p = document.createElement('p');
    p.className = 'success-message';
    p.textContent = `Inscription réussie — bienvenue ${prenom} !`;
    this.form.appendChild(p);
  }
  showError(message) { alert(message); }
}