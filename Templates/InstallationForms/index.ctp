<?php /** * @var \App\View\AppView $this * @var \App\Model\Entity\Employee[]|\Cake\Collection\CollectionInterface $employees */ ?>
<!-- Left navbar-header -->
<div class="row second-nav-padd">
    <?=$this->element('common_nav'); ?></div>
<div class="container-fluid listing-new-design">
    <div class="row">
        <?=$this->Flash->render() ?>
            <div class="col-md-12">
                <div class="white-box white-box-padd shadow">
                    <div class="row bg-title">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <h4 class="page-title">Installation Forms</h4>
                        </div>
                        <!-- <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                            <h3 class="box-title m-b-0 pull-right">
                            <a href="<?= $this->Url->build('/epc/add', true); ?>"><button class="btn btn-default ui-btn"><i class="fa fa-plus"></i> Add Pay Structure</button></a>
                            </h3>
                        </div> -->
                    </div>
                    <div class="row">
                            <div class="table-responsive">
                                <div class='preloader_div'></div>
                                <div id="divUpdate">
                                    <?php echo $this->element('InstallationForms/index'); ?></div>
                            </div>
                    </div>
                </div>
            </div>
    </div>
</div>
