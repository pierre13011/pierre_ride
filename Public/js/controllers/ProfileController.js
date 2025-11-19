import ProfileView from '../views/ProfileView.js';
import UserModel from '../models/UserModel.js';
export default class ProfileController {
  constructor() { this.view = new ProfileView(); this.model = new UserModel(); }
  init() {
    const user = this.model.getUser();
    if (!user) { window.location.href = 'connexion.php'; return; }
    this.view.afficherProfil(user);
    this.view.logoutBtn?.addEventListener('click', e => {
      e.preventDefault();
      this.model.logout();
      window.location.href = 'connexion.php';
    });
  }
}