class CreateStudents < ActiveRecord::Migration
  def change
    create_table :students do |t|
      t.string :name
      t.string :pic
      t.text :profile
      t.integer :coins
      t.references :group
      
      t.timestamps
    end
  end
end
