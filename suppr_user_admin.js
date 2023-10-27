function confirmerSuppression(id) {
    var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
  
    if (confirmation) {
        // Si l'utilisateur confirme la suppression, redirigez vers la page de traitement_suppr_user_admin.php en incluant l'ID de l'utilisateur.
        window.location.href = "traitement_suppr_user_admin.php?id=" + id;
    } else {
        alert("Suppression annulée.");
    }
  }
  