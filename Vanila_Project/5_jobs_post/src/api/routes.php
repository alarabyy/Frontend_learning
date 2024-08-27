<?php
// responsible for defining all API routes
// http://www.domainName.domain/api/{route}
// $router->method(route, controllerName:functionName, params);

// test routes
$router->get('', 'Home');
$router->get('test/{id}/{data}', 'Home:Test');

// Auth routes
$router->post('login', 'User:Login', ['email', 'password'])->only('Guest');
$router->post('register', 'User:Register', ['first_name', 'last_name', 'address', 'industry', 'email', 'password', 'is_recruiter'])->only('Guest');
$router->post('logout', 'User:Logout')->only('Auth');
$router->post('feed', 'User:Feed');
$router->post('user', 'User:Profile', ['id']);

// Post routes
$router->post('post/add', 'Post:AddNewPost', ['position', 'company', 'location', 'salary', 'industry', 'description']);
$router->post('post/comments', 'Post:GetComments', ['id']);
$router->post('post/add/comment', 'Post:AddComment', ['post_id', 'content']);
$router->post('post/apply', 'Post:Apply', ['post_id']);