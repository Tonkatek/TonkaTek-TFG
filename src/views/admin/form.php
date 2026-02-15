<?php require_once __DIR__ . '/../components/navbar.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold tonka-logo">
                <?php echo isset($producto) ? 'Editar Producto' : 'Añadir Producto'; ?>
            </h1>
            <a href="/admin" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al Panel
            </a>
        </div>

        <!-- Mostrar alertas -->
        <?php displayAlert(); ?>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <form method="POST" action="<?php echo isset($producto) ? '/admin/editar/' . $producto['id'] : '/admin/crear'; ?>">
                    
                    <!-- Nombre -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre del Producto *</span>
                        </label>
                        <input type="text" name="nombre" class="input input-bordered" 
                               value="<?php echo isset($producto) ? htmlspecialchars($producto['nombre']) : ''; ?>" 
                               required>
                    </div>

                    <!-- Descripción -->
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">Descripción</span>
                        </label>
                        <textarea name="descripcion" class="textarea textarea-bordered h-24"><?php echo isset($producto) ? htmlspecialchars($producto['descripcion']) : ''; ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Precio -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Precio (€) *</span>
                            </label>
                            <input type="number" step="0.01" name="precio" class="input input-bordered" 
                                   value="<?php echo isset($producto) ? $producto['precio'] : ''; ?>" 
                                   required>
                        </div>

                        <!-- Stock -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Stock *</span>
                            </label>
                            <input type="number" name="stock" class="input input-bordered" 
                                   value="<?php echo isset($producto) ? $producto['stock'] : '0'; ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Marca -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Marca</span>
                            </label>
                            <input type="text" name="marca" class="input input-bordered" 
                                   value="<?php echo isset($producto) ? htmlspecialchars($producto['marca']) : ''; ?>">
                        </div>

                        <!-- Modelo -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Modelo</span>
                            </label>
                            <input type="text" name="modelo" class="input input-bordered" 
                                   value="<?php echo isset($producto) ? htmlspecialchars($producto['modelo']) : ''; ?>">
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">Categoría *</span>
                        </label>
                        <select name="categoria_id" class="select select-bordered" required>
                            <option value="">Selecciona una categoría</option>
                            <?php foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                        <?php echo (isset($producto) && $producto['categoria_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Imagen -->
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">Nombre de Imagen</span>
                            <span class="label-text-alt text-xs">Ejemplo: ryzen5-5600x.jpg</span>
                        </label>
                        <input type="text" name="imagen" class="input input-bordered" 
                               value="<?php echo isset($producto) ? htmlspecialchars($producto['imagen']) : ''; ?>"
                               placeholder="nombre-imagen.jpg">
                    </div>

                    <!-- Especificaciones -->
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text font-semibold">Especificaciones (JSON)</span>
                            <span class="label-text-alt text-xs">Opcional - Formato JSON</span>
                        </label>
                        <textarea name="especificaciones" class="textarea textarea-bordered h-32 font-mono text-sm" 
                                  placeholder='{"nucleos": 6, "frecuencia": "3.7 GHz"}'><?php echo isset($producto) ? htmlspecialchars($producto['especificaciones']) : '{}'; ?></textarea>
                    </div>

                    <!-- Destacado -->
                    <div class="form-control mt-4">
                        <label class="label cursor-pointer justify-start gap-4">
                            <input type="checkbox" name="destacado" class="checkbox checkbox-primary" 
                                   <?php echo (isset($producto) && $producto['destacado']) ? 'checked' : ''; ?>>
                            <span class="label-text font-semibold">Producto Destacado</span>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="card-actions justify-end mt-6 gap-2">
                        <a href="/admin" class="btn btn-ghost">Cancelar</a>
                        <button type="submit" class="btn btn-primary">
                            <?php echo isset($producto) ? 'Actualizar Producto' : 'Crear Producto'; ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
