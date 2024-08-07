<?php

namespace PHPMaker2024\Subastas2024;

// Page object
$ImpuestosEdit = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<main class="edit">
<form name="fimpuestosedit" id="fimpuestosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" autocomplete="off">
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { impuestos: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fimpuestosedit;
loadjs.ready(["wrapper", "head"], function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fimpuestosedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["codnum", [fields.codnum.visible && fields.codnum.required ? ew.Validators.required(fields.codnum.caption) : null], fields.codnum.isInvalid],
            ["porcen", [fields.porcen.visible && fields.porcen.required ? ew.Validators.required(fields.porcen.caption) : null, ew.Validators.float], fields.porcen.isInvalid],
            ["descripcion", [fields.descripcion.visible && fields.descripcion.required ? ew.Validators.required(fields.descripcion.caption) : null], fields.descripcion.isInvalid],
            ["rangos", [fields.rangos.visible && fields.rangos.required ? ew.Validators.required(fields.rangos.caption) : null], fields.rangos.isInvalid],
            ["montomin", [fields.montomin.visible && fields.montomin.required ? ew.Validators.required(fields.montomin.caption) : null, ew.Validators.integer], fields.montomin.isInvalid],
            ["activo", [fields.activo.visible && fields.activo.required ? ew.Validators.required(fields.activo.caption) : null], fields.activo.isInvalid]
        ])

        // Form_CustomValidate
        .setCustomValidate(
            function (fobj) { // DO NOT CHANGE THIS LINE! (except for adding "async" keyword)!
                    // Your custom validation code here, return false if invalid.
                    return true;
                }
        )

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "rangos": <?= $Page->rangos->toClientList($Page) ?>,
            "activo": <?= $Page->activo->toClientList($Page) ?>,
        })
        .build();
    window[form.id] = form;
    currentForm = form;
    loadjs.done(form.id);
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="impuestos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->codnum->Visible) { // codnum ?>
    <div id="r_codnum"<?= $Page->codnum->rowAttributes() ?>>
        <label id="elh_impuestos_codnum" class="<?= $Page->LeftColumnClass ?>"><?= $Page->codnum->caption() ?><?= $Page->codnum->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->codnum->cellAttributes() ?>>
<span id="el_impuestos_codnum">
<span<?= $Page->codnum->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->codnum->getDisplayValue($Page->codnum->EditValue))) ?>"></span>
<input type="hidden" data-table="impuestos" data-field="x_codnum" data-hidden="1" name="x_codnum" id="x_codnum" value="<?= HtmlEncode($Page->codnum->CurrentValue) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->porcen->Visible) { // porcen ?>
    <div id="r_porcen"<?= $Page->porcen->rowAttributes() ?>>
        <label id="elh_impuestos_porcen" for="x_porcen" class="<?= $Page->LeftColumnClass ?>"><?= $Page->porcen->caption() ?><?= $Page->porcen->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->porcen->cellAttributes() ?>>
<span id="el_impuestos_porcen">
<input type="<?= $Page->porcen->getInputTextType() ?>" name="x_porcen" id="x_porcen" data-table="impuestos" data-field="x_porcen" value="<?= $Page->porcen->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->porcen->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->porcen->formatPattern()) ?>"<?= $Page->porcen->editAttributes() ?> aria-describedby="x_porcen_help">
<?= $Page->porcen->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->porcen->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->descripcion->Visible) { // descripcion ?>
    <div id="r_descripcion"<?= $Page->descripcion->rowAttributes() ?>>
        <label id="elh_impuestos_descripcion" for="x_descripcion" class="<?= $Page->LeftColumnClass ?>"><?= $Page->descripcion->caption() ?><?= $Page->descripcion->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->descripcion->cellAttributes() ?>>
<span id="el_impuestos_descripcion">
<input type="<?= $Page->descripcion->getInputTextType() ?>" name="x_descripcion" id="x_descripcion" data-table="impuestos" data-field="x_descripcion" value="<?= $Page->descripcion->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->descripcion->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->descripcion->formatPattern()) ?>"<?= $Page->descripcion->editAttributes() ?> aria-describedby="x_descripcion_help">
<?= $Page->descripcion->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->descripcion->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rangos->Visible) { // rangos ?>
    <div id="r_rangos"<?= $Page->rangos->rowAttributes() ?>>
        <label id="elh_impuestos_rangos" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rangos->caption() ?><?= $Page->rangos->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->rangos->cellAttributes() ?>>
<span id="el_impuestos_rangos">
<template id="tp_x_rangos">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="impuestos" data-field="x_rangos" name="x_rangos" id="x_rangos"<?= $Page->rangos->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_rangos" class="ew-item-list"></div>
<selection-list hidden
    id="x_rangos"
    name="x_rangos"
    value="<?= HtmlEncode($Page->rangos->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_rangos"
    data-target="dsl_x_rangos"
    data-repeatcolumn="5"
    class="form-control<?= $Page->rangos->isInvalidClass() ?>"
    data-table="impuestos"
    data-field="x_rangos"
    data-value-separator="<?= $Page->rangos->displayValueSeparatorAttribute() ?>"
    <?= $Page->rangos->editAttributes() ?>></selection-list>
<?= $Page->rangos->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rangos->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->montomin->Visible) { // montomin ?>
    <div id="r_montomin"<?= $Page->montomin->rowAttributes() ?>>
        <label id="elh_impuestos_montomin" for="x_montomin" class="<?= $Page->LeftColumnClass ?>"><?= $Page->montomin->caption() ?><?= $Page->montomin->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->montomin->cellAttributes() ?>>
<span id="el_impuestos_montomin">
<input type="<?= $Page->montomin->getInputTextType() ?>" name="x_montomin" id="x_montomin" data-table="impuestos" data-field="x_montomin" value="<?= $Page->montomin->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->montomin->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->montomin->formatPattern()) ?>"<?= $Page->montomin->editAttributes() ?> aria-describedby="x_montomin_help">
<?= $Page->montomin->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->montomin->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->activo->Visible) { // activo ?>
    <div id="r_activo"<?= $Page->activo->rowAttributes() ?>>
        <label id="elh_impuestos_activo" for="x_activo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->activo->caption() ?><?= $Page->activo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->activo->cellAttributes() ?>>
<span id="el_impuestos_activo">
    <select
        id="x_activo"
        name="x_activo"
        class="form-select ew-select<?= $Page->activo->isInvalidClass() ?>"
        <?php if (!$Page->activo->IsNativeSelect) { ?>
        data-select2-id="fimpuestosedit_x_activo"
        <?php } ?>
        data-table="impuestos"
        data-field="x_activo"
        data-value-separator="<?= $Page->activo->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->activo->getPlaceHolder()) ?>"
        <?= $Page->activo->editAttributes() ?>>
        <?= $Page->activo->selectOptionListHtml("x_activo") ?>
    </select>
    <?= $Page->activo->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->activo->getErrorMessage() ?></div>
<?php if (!$Page->activo->IsNativeSelect) { ?>
<script>
loadjs.ready("fimpuestosedit", function() {
    var options = { name: "x_activo", selectId: "fimpuestosedit_x_activo" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    if (!el)
        return;
    options.closeOnSelect = !options.multiple;
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fimpuestosedit.lists.activo?.lookupOptions.length) {
        options.data = { id: "x_activo", form: "fimpuestosedit" };
    } else {
        options.ajax = { id: "x_activo", form: "fimpuestosedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.impuestos.fields.activo.selectOptions);
    ew.createSelect(options);
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" form="fimpuestosedit"><?= $Language->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= $Language->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fimpuestosedit" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("impuestos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
