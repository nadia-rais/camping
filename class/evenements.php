<?php

class Evenements{
     

    /**
     * recupère les events entre 2dates soit qui commencent entre le 1er et 30 ou 31  du mois en cours
     */
    public function getEventsBetween (\DateTime $start, \DateTime $end): array
    {
       try
        {
        $db = new PDO('mysql:host=localhost;dbname=camping;charset=utf8', 'root', '');
        }
        catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
        }

        $request_all_events = $db->prepare("SELECT * FROM reservations WHERE date_debut BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'");
        //var_dump( $request_all_events);
        $request_all_events->execute();
        $results_resa = ($request_all_events->fetchAll());
        //var_dump( $results_resa);

        return $results_resa;
    }

     /**
     * recupère les events qui commencent pendant le mois en cours indexés par jour
     */
    public function getEventsBetweenByDay (\DateTime $start, \DateTime $end): array
    {
      $events = $this->getEventsBetween($start,$end);
      $days = [];
      foreach($events as $event){
          //var_dump($event);
          $date = explode (' ',$event['date_debut'])[0];
          $fin = explode  (' ',$event['date_fin'])[0];
          //var_dump ($date);
          //var_dump ($test);

          $datetime1 = (new DateTime($date));
          $datetime2 = (new DateTime($fin));
          $interval = $datetime1->diff($datetime2);
          echo $interval->format('%a');

          if(!isset($days[$date])){
              $days[$date] = [$event];
          } else {
              $days[$date][] = $event;
          }

          if(!isset($days[$fin])){
            $days[$fin] = [$event];
        } else {
            $days[$fin][] = $event;
        }

        
      }
      return $days;

    }

      /**
     * recupère un évenement grâce à l'id de la réservation
     */

     public function find (int $id){

        try
        {
        $db = new PDO('mysql:host=localhost;dbname=camping;charset=utf8', 'root', '');
        }
        catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
        }

        $request_infos_resa = $db->prepare("SELECT * FROM reservations WHERE id_reservation = $id");
        //var_dump($request_id);
        $request_infos_resa->execute();
        $result_infos_resa = ($request_infos_resa->fetch());
        var_dump($result_infos_resa);

        $request_infos_total = $db->prepare("SELECT prix_detail.nb_emplacement, prix_detail.nb_jours, prix_detail.prix_total, detail_lieux.nom_lieu, detail_options.nom_option FROM prix_detail JOIN detail_lieux ON prix_detail.id_reservation = detail_lieux.id_reservation JOIN detail_options ON prix_detail.id_reservation = detail_options.id_reservation WHERE prix_detail.id_reservation = $id");
        //var_dump($request_id);
        $request_infos_total->execute();
        $result_infos_total = ($request_infos_total->fetch());
        var_dump($result_infos_total);

        

        //if($result_id ===)

        return $result_infos_resa + $result_infos_total;

     }

   

    }

     









?>

