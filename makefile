deploy:
	docker build -t laphant:api-php api-php/
	docker build -t laphant:bot bot/
	docker build -t laphant:api-python python/
	docker-compose up -d