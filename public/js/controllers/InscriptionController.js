import InscriptionView from '../views/InscriptionView.js';
import UserModel from '../models/UserModel.js';
export default class InscriptionController {
  constructor() { this.view = new InscriptionView(); this.model = new UserModel(); }
  init() {
    if (!this.view.form) return;
    this.view.form.addEventListener('submit', e => { e.preventDefault(); this.inscrire(); });
  }
  inscrire() {
    const data = this.view.getFormData();
    if (!data.nom || !data.prenom || !data.pseudo || !data.email || !data.password) { this.view.showError('Veuillez remplir tous les champs.'); return; }
    if (data.password.length < 6) { this.view.showError('Le mot de passe doit contenir au moins 6 caractères.'); return; }
    if (data.password !== data.confirm) { this.view.showError('Les mots de passe ne correspondent pas.'); return; }
    this.model.saveUser({ nom: data.nom, prenom: data.prenom, pseudo: data.pseudo, email: data.email, password: data.password, role: data.chauffeur ? 'chauffeur' : 'passager' });
    this.view.showSuccess(data.prenom);
    setTimeout(() => (window.location.href = 'profile.php'), 1000);
  }
}