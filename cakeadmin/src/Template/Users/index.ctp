<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
		<li><?= $this->Html->link(__('Logout'), ['action' => 'logout']) ?></li>
    </ul>
</nav>

<div class="users index large-9 medium-8 columns content">

	<?= $this->Form->create() ?>
	<table><tr><td>
		<?php echo $this->Form->input('username',['placeholder' => 'Username /Email','style'=>'width:450px;','label'=>false]); ?></td><td>
		<?= $this->Form->button(__('Submit')) ?></td></tr></table>
	
	
	<?= $this->Form->end() ?>
	
	
	

    <a href="users"><h3><?= __('Users') ?></h3></a>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
               <!-- <th><?= $this->Paginator->sort('password') ?></th>-->
                <th><?= $this->Paginator->sort('fname') ?></th>
                <th><?= $this->Paginator->sort('lname') ?></th>
                <th><?= $this->Paginator->sort('contact') ?></th>
                <!--<th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>-->
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Number->format($user->id) ?></td>
                <td><?= h($user->username) ?></td>
                <td><?= h($user->email) ?></td>
                <!--<td><?= h($user->password) ?></td>-->
                <td><?= h($user->fname) ?></td>
                <td><?= h($user->lname) ?></td>
                <td><?= h($user->contact) ?></td>
               <!-- <td><?= h($user->created) ?></td>
                <td><?= h($user->modified) ?></td>-->
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
