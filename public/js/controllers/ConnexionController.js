import ConnexionView from '../views/ConnexionView.js';
import UserModel from '../models/UserModel.js';
export default class ConnexionController {
  constructor() { this.view = new ConnexionView(); this.model = new UserModel(); }
  init() {
    if (!this.view.form) return;
    this.view.form.addEventListener('submit', e => { e.preventDefault(); this.connexion(); });
  }
  connexion() {
    const { email, password } = this.view.getCredentials();
    if (!email || password.length < 6) { this.view.showMessage('Email ou mot de passe invalide.', 'err'); return; }
    this.model.saveUser({ email, role: 'passager' });
    this.view.showMessage(`Bienvenue ${email}`, 'info', 1000);
    setTimeout(() => { window.location.href = 'profile.php'; }, 1200);
  }
}