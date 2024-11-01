window.onload = (function () {
	let el = document.getElementById('js-throwback-posts');
	let card = document.getElementById('throwback-posts');
	
	el.addEventListener('click', function () {
		
		let height = 'calc(100% - ' + card.offsetHeight + 'px)';
		if( card.classList.contains('on') ){
			card.style = '';
			card.classList.remove('on');
			return false;
		}
		card.classList.add('on');
		card.style.top = height;
	});
	

	  
});