<?php

class ProductType {
  public static function createProduct($typeId, $sku, $name, $price, $weight = null, $size = null, $height = null, $width = null, $length = null) {
      list($className, $productTypeId) = self::getClassNameAndTypeId($typeId);

      if (class_exists($className)) {
          $productClass = new ReflectionClass($className);

          $productConstructor = $productClass->getConstructor();
          $parameters = $productConstructor->getParameters();
          $arguments = [];

          foreach ($parameters as $parameter) {
              $parameterName = $parameter->getName();
              if ($parameterName === 'typeId') {
                  $arguments[] = $typeId;
              } elseif ($parameterName === 'sku') {
                  $arguments[] = $sku;
              } elseif ($parameterName === 'name') {
                  $arguments[] = $name;
              } elseif ($parameterName === 'price') {
                  $arguments[] = $price;
              } elseif ($parameterName === 'product_type_id') {
                  $arguments[] = $productTypeId; // Use the product type ID instead of type ID
              } elseif ($parameterName === 'weight') {
                  $arguments[] = $weight;
              } elseif ($parameterName === 'size') {
                  $arguments[] = $size;
              } elseif ($parameterName === 'height') {
                  $arguments[] = $height;
              } elseif ($parameterName === 'width') {
                  $arguments[] = $width;
              } elseif ($parameterName === 'length') {
                  $arguments[] = $length;
              } else {
                  $arguments[] = null;
              }
          }

          $product = $productClass->newInstanceArgs($arguments);

          // Save the product to the database with the product type ID
          $product->create($productTypeId); // Use the product type ID instead of type ID

          return $product;
      } else {
          throw new Exception('Invalid product type');
      }
  }

  private static function getClassNameAndTypeId($typeId) {
      // Map type ID to class name and product type ID
      $classMap = [
          1 => ['className' => 'Dvd', 'productTypeId' => 1],
          2 => ['className' => 'Furniture', 'productTypeId' => 2],
          3 => ['className' => 'Book', 'productTypeId' => 3],
      ];

      if (isset($classMap[$typeId])) {
          $classData = $classMap[$typeId];
          return [$classData['className'], $classData['productTypeId']];
      } else {
          throw new Exception('Invalid product type');
      }
  }
}
