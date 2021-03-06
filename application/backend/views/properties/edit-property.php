<?php

use app\backend\widgets\BackendWidget;
use kartik\dynagrid\DynaGrid;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Property edit');
$this->params['breadcrumbs'][] = ['url' => ['/backend/properties/index'], 'label' => Yii::t('app', 'Property groups')];
$this->params['breadcrumbs'][] = ['url' => ['/backend/properties/group', 'id'=>$model->property_group_id], 'label' => $model->group->name];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= app\widgets\Alert::widget([
    'id' => 'alert',
]); ?>

<?php $form = ActiveForm::begin(['id' => 'property-form', 'type'=>ActiveForm::TYPE_HORIZONTAL]); ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?= Html::submitButton(
        Icon::show('save') . Yii::t('app', 'Save'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    ) ?>
</div>
<?php $this->endBlock('submit'); ?>

<?php
$this->beginBlock('add-button');
?>
        <a href="<?= Url::to(['/backend/properties/edit-static-value', 'property_id'=>$model->id]) ?>" class="btn btn-success">
            <?= Icon::show('plus') ?>
            <?= Yii::t('app', 'Add value') ?>
        </a>
<?php
$this->endBlock();
?>

<section id="widget-grid">
    <div class="row">

        <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            <?php BackendWidget::begin(['title'=> Yii::t('app', 'Property'), 'icon'=>'cogs', 'footer'=>$this->blocks['submit']]); ?>

                <?= $form->field($model, 'name')?>

                <?= $form->field($model, 'key')?>

                <?= $form->field($model, 'value_type')->dropDownList(['STRING' => 'string', 'NUMBER' => 'number'])?>

                <?= $form->field($model, 'property_handler_id')->dropDownList(app\models\PropertyHandler::getSelectArray())?>

                <?= $form->field($model, 'has_static_values')->radio(['data-group' => 'property-type']) ?>
                
                <?= $form->field($model, 'has_slugs_in_values')->checkbox() ?>
                
                <?= $form->field($model, 'is_eav')->radio(['data-group' => 'property-type']) ?>
                
                <?= $form->field($model, 'is_column_type_stored')->radio(['data-group' => 'property-type']) ?>

                <?= $form->field($model, 'multiple')->checkbox() ?>

                <?= $form->field($model, 'required')->checkbox() ?>

                <?= $form->field($model, 'interpret_as')->dropDownList(app\models\SpamChecker::getFieldTypesForFormByParentId($fieldinterpretParentId)) ?>

                <?= $form->field($model, 'captcha')->checkbox() ?>

                <?= $form->field($model, 'sort_order') ?>

            <?php BackendWidget::end(); ?>

        </article>

        
        <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            
            <?php BackendWidget::begin(['title'=> Yii::t('app', 'Logic settings'), 'icon'=>'cogs', 'footer'=>$this->blocks['submit']]); ?>
                <?= $form->field($model, 'hide_other_values_if_selected')->checkbox() ?>

                <?= $form->field($model, 'display_only_on_depended_property_selected')->checkbox() ?>
                <?= $form->field($model, 'depends_on_property_id') ?>
                <?= $form->field($model, 'depended_property_values') ?>
                <?= $form->field($model, 'depends_on_category_group_id'); ?>
                <?= $form->field($model, 'dont_filter')->checkbox() ?>

            <?php BackendWidget::end(); ?>

        </article>

    </div>
</section>

<?php ActiveForm::end(); ?>
<script>
jQuery('input[data-group]').change(function() {
    var $this = jQuery(this);
    if ($this.prop('checked')) {
        jQuery('input[data-group="' + $this.data('group') + '"]').not('[name="' + $this.attr('name') + '"]').prop('checked', false);
    }
});
</script>
<?php if ($model->has_static_values): ?>
    <?=
    DynaGrid::widget([
        'options' => [
            'id' => 'property-grid',
        ],
        'columns' => [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id',
            ],
            'name',
            'value',
            'slug',
            [
                'class' => 'app\backend\components\ActionColumn',
                'buttons' => [
                    [
                        'url' => 'edit-static-value',
                        'icon' => 'pencil',
                        'class' => 'btn-primary',
                        'label' => 'Edit',
                    ],
                    [
                        'url' => 'delete-static-value',
                        'icon' => 'trash-o',
                        'class' => 'btn-danger',
                        'label' => 'Delete',
                    ],
                ], // /buttons
                'url_append' => '&property_id='.$model->id.'&property_group_id='.$model->property_group_id,
            ],
        ],
        
        'theme' => 'panel-default',
        
        'gridOptions'=>[
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'hover'=>true,

            'panel'=>[
                'heading'=>'<h3 class="panel-title">'.Yii::t('app', 'Static values').'</h3>',
                'after' => $this->blocks['add-button'],

            ],
            
        ]
    ]);
    ?>
    <?php
endif;
