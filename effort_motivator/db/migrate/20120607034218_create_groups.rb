class CreateGroups < ActiveRecord::Migration
  def change
    create_table :groups do |t|
      t.string :name
      t.text :description
      t.integer :coins
      t.references :teacher

      t.timestamps
    end
  end
end
