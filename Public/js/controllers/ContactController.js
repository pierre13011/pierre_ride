import ContactView from '../views/ContactView.js';
export default class ContactController {
  constructor() { this.view = new ContactView(); }
  init() {
    if (!this.view.form) return;
    this.view.form.addEventListener('submit', e => {
      e.preventDefault();
      this.view.showMessage('Votre message a bien été envoyé !', 'success');
    });
  }
}