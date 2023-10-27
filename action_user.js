function supprimerCompte() {
  var confirmation = confirm("ATTENTION : VOUS ÊTES SUR LE POINT DE SUPPRIMER VOTRE COMPTE.\n\nCette action est irréversible. Souhaitez-vous vraiment continuer ?");
  
  if (confirmation) {
    // Si l'utilisateur confirme la suppression, nous redirigeons vers une page PHP pour effectuer la suppression du compte.
    window.location.href = "traitement_suppr_user.php";
  } else {
    alert("Annulation de la suppression du compte.");
  }
}

