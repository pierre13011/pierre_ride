export default class ContactView {
  constructor() { this.form = document.querySelector('.contact_container form'); }
  showMessage(msg, type = 'info') { alert(`[${type.toUpperCase()}] ${msg}`); }
}