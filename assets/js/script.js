// Pourcentage de stockage libre initial (par exemple, 100%)
let percentage = 80;

// Fonction pour mettre à jour le pourcentage et l'arc
function updateStorage(newPercentage) {
  percentage = newPercentage;
  
  // Calculer l'offset pour le demi-arc (propriétés du demi-cercle)
  let strokeDasharray = 251;  // La longueur du demi-cercle (pi * r)
  let strokeDashoffset = (1 - (percentage / 100)) * strokeDasharray;

  // Appliquer l'offset au demi-arc
  document.getElementById("storage-arc").style.strokeDashoffset = strokeDashoffset;

  // Afficher le pourcentage dans le texte
  document.getElementById("percentage-text").textContent = percentage + "%";
}

// Initialiser avec le pourcentage de 100%
updateStorage(percentage);
