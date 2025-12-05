export default class UserModel {
  getUser() { return JSON.parse(localStorage.getItem('user')) || null; }
  saveUser(user) { localStorage.setItem('user', JSON.stringify(user)); }
  logout() { localStorage.removeItem('user'); }
}