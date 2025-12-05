export default class ProfileView {
  constructor() { this.logoutBtn = document.getElementById('logoutBtn'); }
  afficherProfil(user) {
    if (!user) return;
    document.getElementById('pseudo').textContent = user.pseudo || '';
    document.getElementById('email').textContent = user.email || '';
    document.getElementById('chauffeur').textContent = user.role === 'chauffeur' ? 'Chauffeur' : 'Passager';
  }
}