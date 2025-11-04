<?php
use PHPUnit\Framework\TestCase;
use App\Services\CategoriaService;
use App\Config\Database;

class CategoriaServiceTest extends TestCase {

    private CategoriaService $service;

    protected function setUp(): void {
        // Inicializa a service antes de cada teste
        $this->service = new CategoriaService();

        // Limpar tabela para testes (opcional, garante ambiente limpo)
        $db = Database::getConnection();
        $db->exec("DELETE FROM categoria");
    }

    public function testCreateAndFindById() {
        $nome = "Categoria Teste";
        $id = $this->service->create($nome);

        $this->assertIsNumeric($id, "ID deve ser numérico");

        $categoria = $this->service->findById((int)$id);
        $this->assertNotNull($categoria, "Categoria criada deve existir");
        $this->assertEquals($nome, $categoria['nome'], "Nome deve ser igual ao inserido");
    }

    public function testUpdate() {
        $id = $this->service->create("Categoria Antiga");

        $updated = $this->service->update((int)$id, "Categoria Atualizada");
        $this->assertTrue($updated, "Update deve retornar true");

        $categoria = $this->service->findById((int)$id);
        $this->assertEquals("Categoria Atualizada", $categoria['nome'], "Nome deve ser atualizado");
    }

    public function testDelete() {
        $id = $this->service->create("Categoria Para Deletar");

        $deleted = $this->service->delete((int)$id);
        $this->assertTrue($deleted, "Delete deve retornar true");

        $categoria = $this->service->findById((int)$id);
        $this->assertNull($categoria, "Categoria deve ser null após exclusão");
    }

    public function testList() {
        $this->service->create("Categoria 1");
        $this->service->create("Categoria 2");

        $lista = $this->service->list();
        $this->assertCount(2, $lista, "Deve retornar 2 categorias");

        $categoria1 = $lista[0];
        $this->assertArrayHasKey('id', $categoria1, "Cada item deve ter 'id'");
        $this->assertArrayHasKey('nome', $categoria1, "Cada item deve ter 'nome'");
    }
}