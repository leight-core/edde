<?php

use Edde\Dummy\User\CurrentUserMapper;
use Edde\Dummy\User\UserMapper;
use Edde\Dummy\User\UserRepository;
use Edde\Slim\SlimApp;
use Edde\User\Mapper\ICurrentUserMapper;
use Edde\User\Mapper\IUserMapper;
use Psr\Container\ContainerInterface;

SlimApp::create(
	__DIR__ . '/config.php',
	[
		\Edde\Bridge\User\Repository\UserRepository::class => static function (ContainerInterface $container) {
			return $container->get(UserRepository::class);
		},
		IUserMapper::class                                 => static function (ContainerInterface $container) {
			return $container->get(UserMapper::class);
		},
		ICurrentUserMapper::class                          => static function (ContainerInterface $container) {
			return $container->get(CurrentUserMapper::class);
		},
	]
);
