<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerD86hFbX\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerD86hFbX/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerD86hFbX.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerD86hFbX\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerD86hFbX\App_KernelDevDebugContainer([
    'container.build_hash' => 'D86hFbX',
    'container.build_id' => '27813eb3',
    'container.build_time' => 1594308161,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerD86hFbX');
