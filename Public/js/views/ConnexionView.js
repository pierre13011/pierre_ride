export default class ConnexionView {
  constructor() {
    this.form = document.querySelector('.login_container form');
    this.email = document.getElementById('pseudo');
    this.password = document.getElementById('password');
    this.messages = document.querySelector('.auth-messages');
    this.btnSubmit = document.querySelector('.login_container button[type="submit"]');
  }
  getCredentials() { return { email: this.email.value.trim(), password: this.password.value }; }
  showMessage(text, type = 'info', timeout = 2000) {
    this.messages.innerHTML = `<p class="message message-${type}">${text}</p>`;
    if (timeout > 0) setTimeout(() => (this.messages.innerHTML = ''), timeout);
  }
}