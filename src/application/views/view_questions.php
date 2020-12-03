<div class="container" id="main-content">
    <div class="row mt-3 text-center">
        <div class="col">

            <table>
                <tr>
                    <td>
                        <?php if ($questions): ?>
                        <?php foreach ($questions as $val) : ?>
                            <span><?= $val->question_text; ?></span>
                        <?php endforeach ?>
                        <?php endif ?>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>


</body>

</html>