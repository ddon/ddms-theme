<?php 
   require_once '../../../wp-load.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <title>Dock test</title>

   <style>

      :root {
         --blrt-color-red: #D31145;
         --blrt-color-red-50: #D3114550;
         --blrt-color-blue: #0B2265;
      }

      body {
         background-color: white;
         font-family: "Open Sans", sans-serif;
      }

      h3 a {
         color: var(--blrt-color-blue);

      }

      .docks-list {
         display: flex;
         flex-wrap: wrap;
      }

      .imagemap {
         width: 45%;
         height: auto;
         margin: 0 auto;
         min-width: 600px;
      }
      .admin__dock-page .imagemap {
         width: 100%;
         min-width: unset;
         display: flex;
      }

      .admin__dock-page .imagemap svg {
         width: auto;
         height: 100%;
         margin: 0 auto;
      }

      g[id*="-areas"] path{
         stroke-width: 1px;
         fill: rgba(255, 255, 255, 0.01);
      }

      
      g[id*="-areas"] path.active_area {
         stroke-width: 2px;
         fill: var(--blrt-color-red-50);
         stroke: var(--blrt-color-red) !important;
      }
      
      .admin__dock-page g[id*="-areas"] path:hover {
         stroke-width: 2px;
         fill: rgba(182, 255, 175, 0.5);
         stroke: #04C900 !important;
      }
      
      .admin__dock-page g[id*="-areas"] path.active_area {
         cursor: pointer;
         
      }


      .admin__dock-page {
         width: 80%;
         margin: 0 auto;
      }

      .admin__dock-page h1 {
         text-align: center;
      }

      .admin__dock-page table {
         border-collapse: collapse;
         border-spacing: 0;
         width: 100%;
         border: 1px solid #ddd;
      }

      .admin__dock-page th, .admin__dock-page td {
         text-align: left;
         padding: 8px;
      }

      .admin__dock-page tr:nth-child(even){background-color: #f2f2f2}
      .admin__dock-page .jobs {
         overflow-x:auto;
      }

      .dock-page__section {
         margin-bottom: 50px;
      }

      .admin__dock-page span[class*="-area_stat"] {
         display: flex;
         justify-content: center;
         align-items: center;

         position: absolute;

         color: white;
         background-color: var(--blrt-color-red);
         border-radius: 50%;
         height: 25px;
         width: 25px;

         cursor: default;
         pointer-events: none;
      }

      .ddms-button.button {
         display: inline-block;
         padding: 10px 30px;
         margin: 10px 0 20px 0;

         color: white;
         font-size: 18px;

         background-color: var(--blrt-color-blue);
         text-decoration: none;

         border-radius: 10px;
         border: 2px solid var(--blrt-color-blue);

         box-shadow: 2px 4px 10px #a9a9a9;

         transition: 0.5s background, color;

      }
      .ddms-button.button:hover {
         color: var(--blrt-color-blue);
         background-color: white;
         border: 2px solid var(--blrt-color-blue);
      }

   </style>
</head>

<body>
   <?php
      $dock_post_id = (int) htmlspecialchars( $_GET["dock_id"] );
      
      if ( ! empty ( $dock_post_id ) ):

         /**
          * 
          * Single Dock Template
          */

         $dock_data = [];

         // Getting dock data
         try {
            $dock_data = \DDMS\Entities\Dock::getById($dock_post_id);
         } catch (\Throwable $th) {
            // error_log( $th );
            die("No dock found with dock_id: $dock_post_id");
         }
         
         if ( !empty( $dock_data ) ):

            $dock_active_jobs = \DDMS\Entities\Job::getAllActiveByDockId($dock_data->id);

            // var_dump($dock_active_jobs);
            $status = ( $dock_data->connection ) ? "<span style='color:green;'>online</span>" : "<span style='color:var(--blrt-color-red);'>offline</span>"; ?>
            <div class='admin__dock-page'>
            
               <div class="dock-header">
                  <p><a target="_parent" href="/wp-admin/admin.php?page=ddms-admin"> < Back to docks </a></p>
                  <?php echo "<h1>$dock_data->name | $status | active jobs: " . count($dock_active_jobs) ."</h1>"; ?>
               </div>
               <div id="<?= $dock_data->slug ?>-map" class="imagemap">
                  <?php
                     if ( !empty ( $dock_data->svg_imagemap ) ) {
                        echo $dock_data->svg_imagemap;
                     } else {
                        echo "<p> no imagemap provided </p>";
                     }
                  ?>

                  <?php $area_stats = []; ?>

                  <?php if ( ! empty ( $dock_active_jobs ) ): ?>
                     
                     <?php $job_table_rows = ""; ?>


                     <?php foreach ($dock_active_jobs as $job): ?>
                        
                        <?php
                           // collecting job count for each active area. used for stats lables
                           ( empty( $area_stats[$job->dock_area] ) ) ? $area_stats[$job->dock_area] = 1 : $area_stats[$job->dock_area]++;
                        ?>
                        
                        <?php $area = \DDMS\Entities\Area::getById($job->dock_area); ?>
                        
                        <?php // Rendering dock's active areas ?>
                        <script>
                           window.addEventListener('load', (event) => {
                              jQuery("#a-<?= $job->dock_area ?>").addClass("active_area a-<?= $job->dock_area ?>-mark");
                              jQuery("#a-<?= $job->dock_area ?>").click(function () {
                                 alert(
                                    "Job ID: <?= $job->id ?>\r\n" +
                                    "Job Name: <?= $job->title ?>\r\n" +
                                    "\r\n" +
                                    "Dock: <?= $dock_data->slug ?>\r\n" +
                                    "Area ID: <?= $area->id ?>\r\n" +
                                    "Area Name: <?= $area->title ?>"
                                 );
                              });
                           });
                        </script>

                        <?php
                           // Rendering job's table rows
                           ob_start();
                        ?>

                        <tr>
                           <td><?= $job->id ?></td>
                           <td><?= $job->date_created ?></td>
                           <td><?= $job->title ?></td>
                           <td><?= $job->description ?></td>
                           <td><?= $job->pin ?></td>
                           <td><?= $job->person ?></td>
                           <td><?= $job->company ?></td>
                           <td><?= $area->title ?></td>
                           <td><?= $job->status ?></td>
                        </tr>

                        <?php
                           $job_table_rows .= ob_get_clean();
                        ?>

                     <?php endforeach; ?>

                  <?php endif; ?>
               </div>
               <div class='dock-page__section'>
                        <h2>Active Jobs</h2>
                        <a target="_parent" href="/wp-admin/post-new.php?post_type=job" class="ddms-button button">+ New Job</a>
                        <div class="jobs active_jobs">
                           <table>
                              <tr>
                                 <th>Id</th>
                                 <th>Created</th>
                                 <th>Title</th>
                                 <th>Description</th>
                                 <th>Pin</th>
                                 <th>Person</th>
                                 <th>Company</th>
                                 <th>Area</th>
                                 <th>Status</th>
                              </tr>
                              <?= $job_table_rows ?>
                           </table>
                        </div>
                     </div>
            </div>
            <script>
               window.addEventListener('load', (event) => {

                  // let paths = Array.from(document.getElementsByTagName('path'))
                  let paths = Array.from(document.querySelectorAll('g[id*="-areas"] path:not(.active_area)'))
                  let rects = Array.from(document.querySelectorAll('g[id*="-areas"] rect:not(.active_area)'))
                  let shapes = [...paths, ...rects];

                  shapes.forEach(shape => {
                     if (shape.className.baseVal !== 'active_area') {
                        shape.addEventListener('click', (e) => {
                           alert("Area: " + e.target.id);
                           console.log(e.target.id);
                        })
                     }
                  })

                  <?php //generating job count stat for each active area ?>
                  <?php if ( ! empty ( $area_stats ) ): ?>
                        let areaPos;
                        let statPosOffset = 8;
                              //
                              // experiments with svg text
                              // $("#a-56").after("<text id='sv-text' x=25 y=25 fill='green' style='font: bold 30px sans-serif;'>55</text>");
                              // $(".imagemap").after("<text id='sv-text' x=25 y=25 fill='green' style='font: bold 30px sans-serif;'>55</text>");
                              // console.log( $("text") )
                              // console.log( $("text").position() )
                              // $("text").css({
                              //    'transform' : 'translate(' + 200 +', ' + 300 + ')'
                              // })
                              // console.log( $("text").position() )

                        <?php foreach ($area_stats as $a => $s): ?>
                              areaPos = $('#a-<?= $a ?>').position();
                              $(".imagemap").after("<span class='a-<?= $a ?>-area_stat stat a-<?= $a ?>-mark'><?= $s ?></span>");
                              $(".a-<?= $a ?>-area_stat").css({
                                 top : areaPos.top + statPosOffset,
                                 left : areaPos.left + statPosOffset
                              });
                        <?php endforeach; ?>

                        <?php // re-rendering area's stats position on window resize ?>
                        $( window ).resize(function() {
                           let activeAreas = Array.from( $('.active_area') );

                           activeAreas.forEach(a => {
                              let aNewPos = $(a).position();
                              let aMark = a.className.baseVal.split(' ')[1];

                              $(".stat." + aMark).css({
                                 top : aNewPos.top + statPosOffset,
                                 left : aNewPos.left + statPosOffset
                              });
                           });
                        });
                  <?php endif; ?>

               });
            </script>
            <?php
         endif;
         
      else: ?>

         <p><!-- DDMS admin page style: <?= get_template_directory_uri() . 'ddms-admin-style.css' ?> --></p>
         <div class="docks-list">
            <?php
               $all_docks = \DDMS\Entities\Dock::getAll();
               
               if ( !empty( $all_docks ) ) {
                  
                  foreach ($all_docks as $dock) {

                     $dock_active_jobs = \DDMS\Entities\Job::getAllActiveByDockId($dock->id);

                     // var_dump($dock_active_jobs);
                     $status = ( $dock->connection ) ? "<span style='color:green;'>online</span>" : "<span style='color:var(--blrt-color-red);'>offline</span>"; ?>

                     <div id="<?= $dock->slug ?>-map" class="imagemap">
                        <div class="dock-header">
                           <h3><a href="?dock_id=<?= $dock->id ?>"><?= $dock->name ?></a> <?= $status ?></h3>
                              <h5>Active jobs: <?= count($dock_active_jobs) ?></h5>
                              <?php if ( ! empty ( $dock_active_jobs ) ): ?>
                                 <?php foreach ($dock_active_jobs as $job): ?>
                                    <?php $area = \DDMS\Entities\Area::getById($job->dock_area); ?>

                                    <script>
                                       window.addEventListener('load', (event) => {
                                          jQuery("#a-<?= $job->dock_area ?>").addClass("active_area");
                                          // jQuery("#a-<?= $job->dock_area ?>").click(function () {
                                          //   alert( "Job ID: <?= $job->id ?>\r\n" + "Job Name: <?= $job->title ?>\r\n" + "\r\nDock: <?= $dock->slug ?>\r\n" + "Area ID: <?= $area->id ?>\r\n" + "Area Name: <?= $area->title ?>" );
                                          // });
                                       });
                                    </script>
                                    <?php endforeach; ?>
                              <?php endif; ?>
                        </div>

                        <?php
                           if ( !empty ( $dock->svg_imagemap ) ) {
                              echo $dock->svg_imagemap;
                           } else {
                              echo "<p> no imagemap provided </p>";
                           }
                        ?>
                     </div>
                     <?php
                  }
               }
            ?>
         </div>

      <?php endif; ?>

</body>

</html>