	<!-- footer -->
	<div class="footer container">
		<footer>
			<p>
			<?php 
				$cnpj = ($this->Configuracao->Cnpj != '') ? '<br>CPNJ: '.$this->Configuracao->Cnpj: '';
				$telefone = ($this->Configuracao->Telefone != '') ? '<br><i class="icon icon-phone"></i>&nbsp; '.$this->Configuracao->Telefone : '';
				echo '<strong>'.$this->Configuracao->NomeInstituicao.'</strong>'.$cnpj.$telefone; ?>
			</p>
			<p class="muted"><small>&copy; <?php echo date('Y'); ?> Certifica-μ - Versão: <?php echo $this->VERSAO; ?> - Desenvolvido por João Ricardo</small></p>
		</footer>
	</div>
	</body>
</html>