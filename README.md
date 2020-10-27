# MIDEAWIFI, plugin pour jeedom

**à faire:**

- si on désactive un equipement, ne pas faire le scan lors du save()
- améliorer la gestion en repérant quand un équipement ne répond pas * INFO:msmart.lan:Couldn't connect with Device* présent dans le retour de script
- Si équipement "unsupported" faire message dans centre de notif en plus de l'alert lors du scan?

**possibles problemes:**
- Comme le discover se fait grâce au broadcast, il se peut que la détection ne fonctionne pas suivant la configuration réseau lorsque celle-ci est spéciale (réseau spécial IoT ou autre)  

**Materiel de test:** 
- WR150HJM8 (merci jayknight)  
- WR120HJM8 (merci jayknight) 
- WR125HJM8 (merci jayknight)