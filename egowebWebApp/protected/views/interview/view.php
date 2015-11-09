<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/angular.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/angular-route.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/app.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/egoweb.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/sigma.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/sigma.notes.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.plugins.dragNodes.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.plugins.dragEvents.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.renderers.customEdgeShapes/shape-library.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.renderers.customEdgeShapes/sigma.renderers.customEdgeShapes.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.renderers.customShapes/shape-library.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.renderers.customShapes/sigma.renderers.customShapes.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl().'/js/plugins/sigma.layout.forceAtlas2.min.js');
?>
<script>
study = <?php echo $study ?>;
questions = <?php echo $questions ?>;
ego_id_questions = <?php echo $ego_id_questions ?>;
ego_questions = <?php echo $ego_questions ?>;
alter_questions = <?php echo $alter_questions ?>;
alter_pair_questions = <?php echo $alter_pair_questions ?>;
network_questions = <?php echo $network_questions ?>;
expressions = <?php echo $expressions ?>;
options = <?php echo $options ?>;
interviewId = <?php echo $interviewId ? $interviewId : "undefined" ?>;
answers = <?php echo $answers ?>;
alters = <?php echo $alters ?>;
questionList = <?php echo $questionList ?>;
csrf = '<?php echo Yii::app()->request->csrfToken; ?>';
</script>
<div ng-view></div>