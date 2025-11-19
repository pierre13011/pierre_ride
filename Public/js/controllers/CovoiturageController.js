import CovoiturageModel from '../models/CovoiturageModel.js';
import CovoiturageView from '../views/CovoiturageView.js';
export default class CovoiturageController {
  constructor() { this.model = new CovoiturageModel(); this.view = new CovoiturageView(); }
  init() {
    if (!this.view.form) return;
    this.view.form.addEventListener('submit', e => { e.preventDefault(); this.rechercher(); });
  }
  rechercher() {
    try {
      const { depart, arrivee, date } = this.view.getFormData();
      const trajets = this.model.rechercherTrajets(depart, arrivee, date);
      this.view.afficherResultats(trajets);
    } catch (error) {
      console.error('Erreur lors de la recherche :', error);
      this.view.afficherErreur(error.message);
    }
  }
}