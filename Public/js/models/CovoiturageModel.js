export default class CovoiturageModel {
  constructor() {
    this.covoiturages = [
      { depart: "Paris", 
        arrivee: "Lyon", 
        date: "2025-10-10", 
        heureDepart: "08:00", 
        heureArrivee: "12:00", 
        chauffeur: { nom: "Alice", note: 4.8, photo: "../Public/img/ai_woman.png" }, places: 2, prix: 25, voitureElectrique: true },

      { depart: "Paris", 
        arrivee: "Lille", 
        date: "2025-10-10", 
        heureDepart: "09:00", 
        heureArrivee: "11:30", 
        chauffeur: { nom: "Bob", note: 4.5, photo: "../Public/img/man.jpg" }, places: 1, prix: 18, voitureElectrique: false },

      { depart: "Paris", 
        arrivee: "Marseille", 
        date: "2025-11-07", 
        heureDepart: "08:00", 
        heureArrivee: "12:00", 
        chauffeur: { nom: "Sophie", note: 3.8, photo: "../Public/img/ai_woman.png" }, places: 2, prix: 25, voitureElectrique: false },

      { depart: "Montpelier", 
        arrivee: "Toulon", 
        date: "2025-12-01", 
        heureDepart: "06:00", 
        heureArrivee: "08:30", 
        chauffeur: { nom: "Claude", note: 4.5, photo: "../Public/img/man.jpg" }, places: 1, prix: 18, voitureElectrique: true }  
    ];
  }
  rechercherTrajets(depart, arrivee, date) {
    if (!depart || !arrivee || !date) throw new Error("Veuillez remplir tous les champs.");
    return this.covoiturages.filter(
      t => t.depart.toLowerCase() === depart.toLowerCase() &&
           t.arrivee.toLowerCase() === arrivee.toLowerCase() &&
           t.date === date &&
           t.places > 0
    );
  }
}