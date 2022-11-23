# CHANGELOG

## Nouvelle Beta à venir - 2022-12-01
+ **Ne faites pas la mise à jour si vous avez des scénarios qui tournent et que vous ne voulez pas tout perdre**  
+ Une nouvelle version du plugin va arriver en beta prochainement. Elle implique pas mal de changements:  
+ - Utilisation d'un nouveau script fonctionnel cloud et non cloud  
+ - Compatible AC et Déshumidificateur  
+ - Il faudra supprimer vos équipements du fait du nouveau fonctionnement  
+ - Compatible uniquement jeedom 4.2 et supérieur  
+ La partie commandes pour déshumidificateur n'est pas implantée, juste la remontée des informations. **A la recherche de testeurs**  
+ Il est conseillé de faire des tests sur une machine virtuelle par exemple, avant d'appliquer la nouvelle beta qui devrait arriver début décembre.  +
+ Pour info, je passerais sur [ce script](https://github.com/nbogojevic/midea-beautiful-air) qui permet plus de choses.  


## 1.0.6beta - 2021-09-15  
+ Fix de bug et tests ok avec un premier utilisateur pour un déshumidificateur cloud  
+ /!\ Pour utiliser cette beta il faut supprimer vos équipements et relancer les dépendances  
  
## 1.0.5beta - 2021-07-01
+ Ajout d'un mode dehumidifier permettant une gestion via le cloud

## 2021-06-03
+ Ajout d'informations dans la documentation + annulation branche beta

## 1.0.4 - 2020-12-10
+ Fix problème d'affectation d'une consigne de température via un scénario

## 1.0.3 - 2020-11-27
+ Fix du problème de design de l'action consigne pour les jeedom v3

## 1.0.2 - 2020-11-19
+ Correction du sous-type de l'action pour l'action du changement de température. Le changement de consigne en saisissant une valeur est désormais possible dans un scénario.

## 1.0.1 - 2020-11-13  
+ Possibilité d'ajouter manuellement un appareil à partir de l'adresse IP, de l'ID et du port

## 1.0.0 - 2020-10-23  
+ Version initiale
