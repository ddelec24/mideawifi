# MIDEAWIFI, plugin pour jeedom

**à faire:**

- install.php avec install des dépendances (to check)
- gestion check et réinstall dépendances (to check)
- logs python dans un fichier? (to check)  
~~- quand on change le timeout dans la page de config, mettre à jour le fichier cli.py, pas réellement utile finalement en usage réel~~  
- Récupérer les infos dès le scan pour avoir des valeurs (to check)

**possible problemes:**
- module click introuvable, ya eu un ticket ça sera peut etre resolu
- il faut une version python entre 3.5 et 3.7, (to check)
- avec une VM, il y a un soucis de locale à cause du module click (to check, ok avec un forcing de locales en php avant l'appel du script)  
- actuellement, le operational_mode est completement bugué meme sur l'appli smartphone, donc pas possible de changer mode clim chaleur 
	- => il semblerait que ça soit uniquement avec jayknight, investigations et contact fournisseur en cours


**Materiel de test:** 
- WR150HJM8 (merci jayknight)  
- WR120HJM8 (merci jayknight) 
- WR125HJM8 (merci jayknight)