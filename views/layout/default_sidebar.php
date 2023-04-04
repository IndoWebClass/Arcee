<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasExampleLabel">Arcee</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        use app\core\StoredProcedure;
        $sp = new StoredProcedure();
        $rows = $sp->setName("sp_auth_getModulPageAuthorization")
            ->setParameters(["i_user" => $_SESSION["arcee"]["userId"]])
            ->prepare()
            ->execute()
            ->fetch();

        $modulesAndPages = [];
        foreach($rows AS $row)
        {
          $moduleId = $row["moduleId"];

          if(!isset($modulesAndPages[$moduleId]))
          {
            $modulesAndPages[$moduleId] = [
              "id" => $moduleId
              ,"name" => $row["moduleName"]
              ,"route" => $row["moduleRoute"]
              ,"fontAwesome" => $row["moduleFontAwesome"]
              ,"pages" => []
            ];
          }

          $modulesAndPages[$moduleId]["pages"][] = [
            "id" => $row["pageId"]
            ,"name" => $row["pageName"]
            ,"route" => $row["pageRoute"]
            ,"fontAwesome" => $row["pageFontAwesome"]
          ];
        }

        foreach($modulesAndPages AS $moduleId => $module)
        {
            echo "<p><a href='/arcee/{$module["route"]}'><i class='{$module["fontAwesome"]} fa-fw'></i> {$module["name"]}</a></p>";

            foreach($module["pages"] AS $page)
            {
              echo "<p class='ms-4'><a href='/arcee/{$module["route"]}/{$page["route"]}'><i class='{$page["fontAwesome"]} fa-fw'></i> {$page["name"]}</a></p>";
            }
        }
        ?>

        <div id="logout">
            <p onClick="logout();"><i class="fa-solid fa-right-from-bracket fa-fw"></i> Log out</p>
        </div>
    </div>
  </div>
