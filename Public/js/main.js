import ConnexionController from './controllers/ConnexionController.js';
import ContactController from './controllers/ContactController.js';
import InscriptionController from './controllers/InscriptionController.js';
import ProfileController from './controllers/ProfileController.js';
import CovoiturageController from './controllers/CovoiturageController.js';

document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector('.login_container')) new ConnexionController().init();
  if (document.getElementById('form-inscription')) new InscriptionController().init();
  if (document.getElementById('logoutBtn')) new ProfileController().init();
  if (document.getElementById('searchForm')) new CovoiturageController().init();
  if (document.querySelector('.contact_container')) new ContactController().init();
});