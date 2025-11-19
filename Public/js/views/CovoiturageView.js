export default class CovoiturageView {
  constructor() { this.form = document.getElementById('searchForm'); this.resultsDiv = document.getElementById('results'); }
  getFormData() {
    return {
      depart: document.getElementById('depart')?.value.trim(),
      arrivee: document.getElementById('arrivee')?.value.trim(),
      date: document.getElementById('date')?.value
    };
  }
  afficherResultats(trajets) {
    this.resultsDiv.innerHTML = '';
    if (trajets.length === 0) {
      this.resultsDiv.innerHTML = `<div class="no-result"><h2>Aucun trajet trouvé</h2><p>Aucun voyage ne correspond.</p></div>`;
      return;
    }
    trajets.forEach(t => {
      const ecologique = t.voitureElectrique ? 'Écologique' : 'Standard';
      this.resultsDiv.innerHTML += `<div class="card"><div class="info"><img src="${t.chauffeur.photo}" alt="chauffeur"><div><strong>${t.chauffeur.nom}</strong><br><span>${t.chauffeur.note}</span></div></div><p><strong>${t.depart}</strong> ➜ <strong>${t.arrivee}</strong></p><p>${t.heureDepart} - ${t.heureArrivee}</p><p>${t.prix} €</p><p>${ecologique}</p><p>Places restantes : ${t.places}</p></div>`;
    });
  }
  afficherErreur(message) { this.resultsDiv.innerHTML = `<div class="error-message"><p>${message}</p></div>`; }
}