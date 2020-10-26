# MIDEAWIFI, plugin pour jeedom

**à faire:**

- Mettre les résultats de scan en forme de façon plus propre
- si on désactive un equipement, ne pas faire le scan lors du save()
- améliorer la gestion en repérant quand un équipement ne répond pas * INFO:msmart.lan:Couldn't connect with Device* présent dans le retour de script


**possible problemes:**
- Comme le discover se fait grâce au broadcast, il se peut que la détection ne fonctionne pas suivant la configuration réseau lorsque celle-ci est spéciale (réseau spécial IoT ou autre)  

**Materiel de test:** 
- WR150HJM8 (merci jayknight)  
- WR120HJM8 (merci jayknight) 
- WR125HJM8 (merci jayknight)