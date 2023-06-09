<?php
?>
<main id="login" class="row g-0" style="height:100vh">
    <div id="login_left" class="col-xxl-3 col-xl-4 col-lg-5 col-12 p-5">
        <div id="logo" class="pt-5 d-flex">
            <img src=""/>
            <p class="h2">Arcee</p>
        </div>

        <?php
        $formParams = [
            "id" => "login",
            "label" =>["isShow" => false],
            "buttons" => ["submitText" => "LOG IN"],
            "errorMessage" =>["isShow" => true],
            "ajax" => ["isRender" => false]
        ];
        $form = new \app\core\Form($formParams);
        $form->addInput(["input" => ["name" => "userName", "placeholder" => "User Name"]]);
        $form->addInput(["input" => ["name" => "password", "placeholder" => "Password", "type" => "password"]]);
        $form->render();

        ?>
        <div class="text-center text-primary mt-5 text-decoration-underline" role="button">Forgot password</div>
    </div>
    <div id="login_right" class="bg-warning col-xxl-9 col-xl-8 col-lg-7 d-lg-block d-none"></div>
</main>
