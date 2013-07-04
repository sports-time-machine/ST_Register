<div class="mirror index">
	<h2>選手と記録の同期</h2>

	<p>
		<span style="font-size: 14pt; font-weight: bold;">選手: </span>
		<?php if (0 < $player_update_num): ?>
			<?php echo $player_lastupdate_time; ?> 以降同期されていないデータが <?php echo $player_update_num; ?> 件あります
		<?php else: ?>
			全てのデータが同期されています
		<?php endif; ?>
	</p>
	<p>
		<span style="font-size: 14pt; font-weight: bold;">記録: </span>
		<?php if (0 < $record_update_num): ?>
			<?php echo $record_lastupdate_time; ?> 以降同期されていないデータが <?php echo $record_update_num; ?> 件あります
		<?php else: ?>
			全てのデータが同期されています
		<?php endif; ?>
	</p>

	<p>
		<a class="btn btn-primary" href="<?php echo $this->Html->url('/mirror/execute/', true); ?>" onclick="return confirm('同期を実行します。よろしいですか？')">Webサーバーへ同期する</a>
	</p>
</div>