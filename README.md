<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestion d’un Complexe Sportif</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }
    header {
      background-color: #007bff;
      color: white;
      text-align: center;
      padding: 40px 20px;
    }
    header h1 {
      margin: 0;
      font-size: 2.5rem;
    }
    header p {
      font-size: 1.2rem;
    }
    section {
      background: white;
      margin: 20px auto;
      padding: 40px 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      max-width: 1000px;
    }
    h2 {
      color: #007bff;
      margin-bottom: 16px;
      font-size: 1.8rem;
    }
    ul {
      padding-left: 20px;
    }
    .row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .column {
      flex: 1;
      min-width: 280px;
    }
    img {
      width: 100%;
      border-radius: 10px;
      object-fit: cover;
    }
    @media (max-width: 768px) {
      header h1 {
        font-size: 2rem;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>🏋️‍♂️ Projet de Gestion d’un Complexe Sportif</h1>
  <p>Une plateforme moderne pour coachs, clients et administrateurs</p>
</header>

<section>
  <h2>🎯 Objectif du projet</h2>
  <p>Développer une <strong>plateforme innovante</strong> (site web + application desktop) pour gérer les activités d’un complexe sportif : cours, abonnements, réservations, boutique, matchs, etc.</p>
  <img src="https://images.unsplash.com/photo-1584467735871-a6f34bdf63be" alt="Salle de sport moderne">
</section>

<section>
  <h2>🔐 Authentification</h2>
  <p>Trois profils d’utilisateurs :</p>
  <ul>
    <li>👤 Client</li>
    <li>🧑‍🏫 Coach</li>
    <li>🛠️ Administrateur</li>
  </ul>
  <p>Chacun doit s’authentifier pour accéder aux fonctionnalités.</p>
</section>

<section>
  <h2>📅 Gestion des cours</h2>
  <div class="row">
    <div class="column">
      <ul>
        <li>Le coach crée et planifie des cours</li>
        <li>Le client s’inscrit selon son planning</li>
      </ul>
    </div>
    <div class="column">
      <img src="https://images.unsplash.com/photo-1605296867304-46d5465a13f1" alt="Cours collectif en salle">
    </div>
  </div>
</section>

<section>
  <h2>📝 Abonnements & Réservations</h2>
  <div class="row">
    <div class="column">
      <ul>
        <li>Réservation d’un service ou abonnement</li>
        <li>Réception d’un badge d’accès</li>
        <li>Coach : consultation des réservations</li>
        <li>Admin : création, validation, classification</li>
      </ul>
    </div>
    <div class="column">
      <img src="https://images.unsplash.com/photo-1589571894960-20bbe2828a27" alt="Badge d’abonnement sportif">
    </div>
  </div>
</section>

<section>
  <h2>🛍️ Produits & Boutique</h2>
  <div class="row">
    <div class="column">
      <ul>
        <li>Le client consulte les produits</li>
        <li>Ajoute au panier</li>
        <li>Effectue un paiement intégré</li>
        <li>L’admin gère produits et catégories</li>
      </ul>
    </div>
    <div class="column">
      <img src="https://images.unsplash.com/photo-1599058917211-f2c0f4bd4a3e" alt="Boutique d’articles de sport">
    </div>
  </div>
</section>

<section>
  <h2>⚽ Terrains & Joueurs</h2>
  <div class="row">
    <div class="column">
      <ul>
        <li>Admin : création et organisation des terrains</li>
        <li>Client : participation aux matchs</li>
      </ul>
    </div>
    <div class="column">
      <img src="https://images.unsplash.com/photo-1584466991123-409d8a6d3ed6" alt="Match de football">
    </div>
  </div>
</section>

<section>
  <h2>⚙️ Aspects techniques</h2>
  <ul>
    <li>Utilisation d’APIs modernes</li>
    <li>Architecture modulaire avec services métiers</li>
    <li>UX/UI optimisée pour une navigation fluide</li>
  </ul>
</section>

</body>
</html>
