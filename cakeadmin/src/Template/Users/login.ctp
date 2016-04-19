<div  id="actions-sidebar">
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Register'), ['action' => 'add']) ?></li>
    </ul>
</div>

<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->input('email') ?>
<?= $this->Form->input('password') ?>
<?= $this->Form->button('Login') ?>
<?= $this->Form->end() ?>