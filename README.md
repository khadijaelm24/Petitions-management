# Petitions-management

This web application aims to manage petitions (Creation, signing, display, modification, deletion (petition and/or signature), ...), through the following steps:

- We use the following two classes:
  
  – <i>Petition</i>: IDP, Titre, Theme, Description, DatePublic, DateFin
  – <i>Signature</i>: IDP, IDS, Nom, Prenom, Pays, Date, Heure
  
- We create the **Petition** database to store petitions and signatures.

- We create the **ListePetition** page to display the list of petitions.

- We create the **Signature** form to sign a petition. This form is accessed from the **ListePetition** page.
  
   – This form consists of the fields: Nom, Prénom, Email, Pays and the Button (envoyer)

- We create the **AjouterSignature** page, which, when called from the **Signature** page:
  
   – adds the new signature to the Signature table
   – displays <i>OK</i> or <i>NotOK</i> based on the success of the operation

- We add the following features:
  
   – Interface for adding a new petition
   – Notification of the addition of a new petition to connected browsers
   – Display in real-time the petition with the most signatures by theme

- And finally, we add a text area in the **Signature** form to display the last five signatures added.
  
   – Use the <i>**XMLHttpRequest**</i> object to update the list asynchronously
