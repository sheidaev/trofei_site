//npm install pg
//npm install @types/pg --save-dev
import { Pool, PoolClient } from 'pg';

type TableData = Record<string, any[]>;
type InsertedIds = Record<string, number[]>;

export class DatabaseHelper {
  private pool: Pool;
  private client: PoolClient | null = null;
  private insertedData: { table: string; ids: number[] }[] = [];

  constructor() {
    this.pool = new Pool({
      host: process.env.DB_HOST || 'db',
      port: parseInt(process.env.DB_PORT || '5432'),
      database: process.env.DB_NAME || 'trofei',
      user: process.env.DB_USER || 'trofei_user',
      password: process.env.DB_PASSWORD || 'trofei_pass',
    });
  }

  async connect() {
    this.client = await this.pool.connect();
  }

  async disconnect() {
    if (this.client) {
      this.client.release();
      this.client = null;
    }
  }

  async insertFixtureData(fixtureData: { db: TableData }): Promise<InsertedIds> {
    if (!this.client) throw new Error('Database not connected');
    
    const insertedIds: InsertedIds = {};
    
    try {
      await this.client.query('BEGIN');
      
      for (const [tableName, records] of Object.entries(fixtureData.db)) {
        if (!Array.isArray(records)) continue;
        
 //       console.log(`Inserting ${records.length} records into ${tableName}...`);
        const ids: number[] = [];
        for (const record of records) {
          const columns = Object.keys(record);
          const values = Object.values(record);
          const placeholders = values.map((_, i) => `$${i + 1}`).join(', ');
          
          const result = await this.client.query(
            `INSERT INTO ${tableName} (${columns.join(', ')})
             VALUES (${placeholders})
             RETURNING id`,
            values
          );
          
          ids.push(result.rows[0].id);
        }
        
        this.insertedData.push({ table: tableName, ids });
        insertedIds[tableName] = ids;
 //       console.log(`Inserted ${ids.length} records into ${tableName}`);
      }
      
      await this.client.query('COMMIT');
      return insertedIds;
    } catch (e) {
      console.error('Error during insert:', e);
      await this.client.query('ROLLBACK');
      throw e;
    }
  }

  async rollback() {
    if (!this.client) throw new Error('Database not connected');
    
    //console.log('Starting rollback...');
    //console.log('Data to rollback:', this.insertedData);
    
    try {
      await this.client.query('BEGIN');
      
      // Видаляємо дані в зворотньому порядку для уникнення проблем з foreign keys
      for (const { table, ids } of this.insertedData.reverse()) {
        if (ids.length > 0) {
//          console.log(`Deleting ${ids.length} records from ${table}...`);
          await this.client.query(
            `DELETE FROM ${table} WHERE id = ANY($1)`,
            [ids]
          );
        }
      }
      
      await this.client.query('COMMIT');
      this.insertedData = [];
    } catch (e) {
      console.error('Error during rollback:', e);
      await this.client.query('ROLLBACK');
      throw e;
    }
  }
} 